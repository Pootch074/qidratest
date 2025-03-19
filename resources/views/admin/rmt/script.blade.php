<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("userTable", () => ({
            users: [],
            showModal: false,
            editMode: false,
            newUser: {
                id: null,
                first_name: "",
                last_name: "",
                user_type: "",
                position: "",
                lgu: "",
                status: "active"
            },

            async fetchUsers() {
                try {
                    const response = await fetch("http://localhost/api/users");
                    this.users = await response.json();
                } catch (error) {
                    console.error("Error fetching users:", error);
                }
            },

            openModal(isEdit) {
                this.editMode = isEdit;
                this.showModal = true;

                if (!isEdit) {
                    this.newUser = {
                        id: null,
                        first_name: "",
                        last_name: "",
                        user_type: "",
                        position: "",
                        lgu: "",
                        status: "active"
                    };
                }
            },

            async addUser() {
                try {
                    const response = await fetch("http://localhost/api/users", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newUser),
                    });

                    if (!response.ok) throw new Error("Failed to add user");

                    const addedUser = await response.json();
                    this.users.push(addedUser.user); // Append new user to table
                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error adding user:", error);
                }
            },

            editUser(user) {
                this.openModal(true);
                this.newUser = {
                    ...user
                }; // Load selected user into form
                this.editMode = true;
                this.showModal = true;
            },

            async updateUser() {
                try {

                    const response = await fetch(
                        'http://localhost/api/users/' + this.newUser.id, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newUser),
                        });

                    if (!response.ok) throw new Error("Failed to update user");

                    // Find and update the user in the list
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
                    const response = await fetch(`http://localhost/api/users/${userId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete user");

                    this.users = this.users.filter(user => user.id !==
                    userId); // Remove user from table
                } catch (error) {
                    console.error("Error deleting user:", error);
                }
            }
        }));
    });
</script>
