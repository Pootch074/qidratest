<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("userTable", () => ({
            search: '',
            users: [],
            user_types: @json($userTypes),
            lgus: @json($lgus),
            showModal: false,
            editMode: false,
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
                this.editMode = isEdit;
                this.showModal = true;

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
                    const response = await fetch("{{ route('api-users-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newUser),
                    });

                    if (!response.ok) throw new Error("Failed to add user");

                    const addedUser = await response.json();

                    let userUpdated = {
                        ...addedUser.user,
                        user_type: this.updateUserType(addedUser.user.user_type_id),
                        lgu: this.updateLgu(addedUser.user.lgu_id)
                    };

                    this.users.push(userUpdated);

                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error adding user:", error);
                }
            },

            editUser(user) {
                this.openModal(true);
                this.newUser = {
                    ...user,
                    password: ""
                }; // Load selected user into form, reset password

                this.editMode = true;
                this.showModal = true;
            },

            async updateUser() {
                try {

                    const response = await fetch(
                            "{{ route('api-users-put', ':id') }}".replace(':id', this.newUser.id), {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newUser),
                        });

                    if (!response.ok) throw new Error("Failed to update profile");

                    // user types
                    let userTypesArray = Object.entries(this.user_types).map(([id, name]) => ({
                        id: Number(id),
                        name
                    }));

                    let selectedUserType = userTypesArray.find(u => u.id === Number(this.newUser.user_type)); // Convert to number
                    this.newUser.user_type = selectedUserType ? selectedUserType.name : "";
                    // end user types

                    // lgu
                    let selectedLgu = this.lgus.find(l => l.id === Number(this.newUser.lgu)); // Convert to number
                    this.newUser.lgu = selectedLgu ? selectedLgu.name : "";
                    // end lgu

                    // Find and update the profile in the list
                    const index = this.users.findIndex(user => user.id === this.newUser.id);
                    if (index !== -1) this.users[index] = {
                        ...this.newUser
                    };

                    this.showModal = false; // Close modal
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
