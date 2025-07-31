<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("userTable", () => ({
            search: '',
            users: [],
            user_types: @json($userTypes),
            lgus: @json($lgus),
            showModal: false,
            editMode: false,
            validationErrors: [],
            newUser: {
                id: null,
                first_name: "",
                last_name: "",
                email: "",
                password: "",
                user_type: "",
                position: "",
                lgu: "",
                status: "Active"
            },

            async fetchUsers() {
                try {
                    const response = await fetch("{{ route('api-users-get') }}");
                    this.users = await response.json();
                } catch (error) {
                    console.error("Error fetching users:", error);
                }
            },

            updateLgu(id)
            {
                let lguName = "-"; // Default value in case no match is found

                for (let i = 0; i < this.lgus.length; i++) {
                    let lgu = this.lgus[i]; // Get the current province object

                    // Convert both to the same type for a reliable comparison
                    if (lgu.id == id) {
                        lguName = lgu.name; // Assign the matched name
                        break; // Stop looping once a match is found
                    }
                }

                return lguName
            },

            updateUserType(id)
            {

                let userTypeName = '-';

                let userTypesArray = Object.entries(this.user_types).map(([id, name]) => ({
                    id: Number(id),
                    name
                }));


                for (let i = 0; i < userTypesArray.length; i++) {
                    let type = userTypesArray[i]; // Get the current object

                    // Convert both to the same type for a reliable comparison
                    if (type.id == id) {
                        userTypeName = type.name; // Assign the matched name
                        break; // Stop looping once a match is found
                    }
                }

                return userTypeName;

            },

            openModal(isEdit) {
                this.validationErrors = []; // Force clear early
                this.editMode = isEdit;
                this.showModal = true;
                console.log(this.validationErrors);

                if (!isEdit) {
                    this.newUser = {
                        id: null,
                        first_name: "",
                        last_name: "",
                        email: "",
                        user_type: "",
                        position: "",
                        lgu: "",
                        status: "Active"
                    };
                }
            },

            async addUser() {
                try {
                    // Prefer the numeric id bound by the select
                    const user_type_id = (this.newUser.user_type_id ?? this.newUser.user_type ?? null);
                    // Your LGU select currently binds to `newUser.lgu` (string) and sometimes has `lgu_id`
                    const lgu_id =
                        this.newUser.lgu_id != null && this.newUser.lgu_id !== ''
                            ? Number(this.newUser.lgu_id)
                            : (Number(this.newUser.lgu) || null);

                    const payload = {
                        first_name: this.newUser.first_name,
                        last_name: this.newUser.last_name,
                        email: this.newUser.email,
                        password: this.newUser.password,
                        position: this.newUser.position,
                        status: this.newUser.status,
                        // IMPORTANT: API expects `user_type` (the id), not the label
                        user_type: user_type_id != null ? Number(user_type_id) : null,
                        lgu_id: lgu_id,
                    };

                    const response = await fetch("{{ route('api-users-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                        },
                        body: JSON.stringify(payload),
                    });

                    if (response.status === 422) {
                        const { errors } = await response.json();
                        this.validationErrors = Object.values(errors || {}).flat();
                        return;
                    }
                    if (!response.ok) throw new Error("Failed to add user");

                    const added = await response.json();
                    const u = added.user;

                    // Keep ids, but convert to labels for display in the table
                    const row = {
                        ...u,
                        user_type: this.updateUserType(u.user_type_id), // label for table cell
                        lgu: this.updateLgu(u.lgu_id),                  // label for table cell
                    };

                    this.users.unshift(row);
                    this.showModal = false;
                } catch (error) {
                    console.error("Error adding user:", error);
                }
            },

            editUser(user) {
                this.validationErrors = []; // Force clear before modal and form is set
                this.newUser = {
                    ...user,
                    password: ""
                }; // Load selected user into form, reset password

                this.openModal(true);
            },

            async updateUser() {
                try {
                    const user_type_id = (this.newUser.user_type_id ?? this.newUser.user_type ?? null);
                    const lgu_id =
                        this.newUser.lgu_id != null && this.newUser.lgu_id !== ''
                            ? Number(this.newUser.lgu_id)
                            : (Number(this.newUser.lgu) || null);

                    const payload = {
                        id: this.newUser.id,
                        first_name: this.newUser.first_name,
                        last_name: this.newUser.last_name,
                        email: this.newUser.email,
                        password: this.newUser.password, // leave as-is; omit if your API requires
                        position: this.newUser.position,
                        status: this.newUser.status,
                        user_type: user_type_id != null ? Number(user_type_id) : null, // <-- send id
                        lgu_id: lgu_id,                                                 // <-- send id
                    };

                    const response = await fetch(
                        "{{ route('api-users-put', ':id') }}".replace(':id', this.newUser.id),
                        {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                            },
                            body: JSON.stringify(payload),
                        }
                    );

                    if (response.status === 422) {
                        const { errors } = await response.json();
                        this.validationErrors = Object.values(errors || {}).flat();
                        return;
                    }
                    if (!response.ok) throw new Error("Failed to update profile");

                    const updated = await response.json();
                    const u = updated.user;

                    // Update the row in the table: keep ids, show labels
                    const idx = this.users.findIndex(x => x.id === this.newUser.id);
                    if (idx !== -1) {
                        this.users[idx] = {
                            ...u,
                            user_type: this.updateUserType(u.user_type_id),
                            lgu: this.updateLgu(u.lgu_id),
                        };
                    }

                    this.showModal = false;
                } catch (error) {
                    console.error("Error updating user:", error);
                }
            },

            async deleteUser(userId) {
                if (!confirm("Are you sure you want to delete this user?")) return;

                try {
                    const response = await fetch(`{{ url('api/users')  }}/${userId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete user");

                    this.users = this.users.filter(user => user.id !==
                    userId); // Remove user from table
                } catch (error) {
                    console.error("Error deleting user:", error);
                }
            },

            //-- for pagination and search
            get filteredUsers() {
                console.log('Users:', this.users); // Debug: Check if users are populated
                console.log('Search Query:', this.search); // Debug: Check the search query

                if (!this.users || this.users.length === 0) return []; // Prevent errors if users are empty

                return this.users.filter(user =>
                    Object.values(user).some(value => {
                        if (value === null || value === undefined) return false; // Skip null/undefined values
                        return String(value).toLowerCase().includes(this.search.toLowerCase());
                    })
                );
            },

            perPage: 10,
            currentPage: 1,

            get paginatedUsers() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;

                return this.filteredUsers.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.filteredUsers.length / this.perPage);
            },

            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },

            init() {
                this.fetchUsers();
            },

            watch: {
                search(value) {
                    console.log('Search updated:', value);
                    this.currentPage = 1; // Reset to first page on search
                }
            },
            //-- end for pagination
        }));
    });
</script>
