<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("lguTable", () => ({
            lgus: [],
            showModal: false,
            editMode: false,
            newLgu: {
                id: null,
                name: "",
                province_id: "",
                region_id: "",
                lgu_type: "",
                office_address: "",
                telephone: "",
                mobile_number: "",
                email_address: ""
            },

            async fetchLgus() {
                try {
                    const response = await fetch("http://localhost/api/lgu");
                    this.lgus = await response.json();
                } catch (error) {
                    console.error("Error fetching lgus:", error);
                }
            },

            openModal(isEdit) {
                this.editMode = isEdit;
                this.showModal = true;

                if (!isEdit) {
                    this.newLgu = {
                        id: null,
                        name: "",
                        province_id: "",
                        region_id: "",
                        lgu_type: "",
                        office_address: "",
                        telephone: "",
                        mobile_number: "",
                        email_address: ""
                    };
                }
            },

            async addLgu() {
                try {
                    const response = await fetch("http://localhost/api/lgu", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newLgu),
                    });

                    if (!response.ok) throw new Error("Failed to add profile");

                    const addedLgu = await response.json();
                    this.lgus.push(addedLgu.lgu); // Append new LGU to table
                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error adding profile:", error);
                }
            },

            editLgu(lgu) {
                this.openModal(true);
                this.newLgu = {
                    ...lgu
                }; // Load selected profile into form
                this.editMode = true;
                this.showModal = true;
            },

            async updateLgu() {
                try {

                    const response = await fetch(
                        'http://localhost/api/lgu/' + this.newLgu.id, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newLgu),
                        });

                    if (!response.ok) throw new Error("Failed to update profile");

                    // Find and update the profile in the list
                    const index = this.lgus.findIndex(lgu => lgu.id === this.newLgu.id);
                    if (index !== -1) this.lgus[index] = {
                        ...this.newLgu
                    };

                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error updating profile:", error);
                }
            },

            async deleteLgu(lguId) {
                if (!confirm("Are you sure you want to delete this profile?")) return;

                try {
                    const response = await fetch(`http://localhost/api/lgu/${lguId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete profile");

                    this.lgus = this.lgus.filter(lgu => lgu.id !==
                    lguId); // Remove profile from table
                } catch (error) {
                    console.error("Error deleting profile:", error);
                }
            }
        }));
    });
</script>
