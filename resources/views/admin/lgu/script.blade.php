<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("lguTable", () => ({
            lgus: [],
            regions: @json($regions),
            provinces: @json($provinces),
            lgu_types: @json($lguTypes),
            showModal: false,
            editMode: false,
            validationErrors: [],
            newLgu: {
                id: null,
                name: "",
                province_id: "",
                province: "",
                region_id: "",
                region: "",
                lgu_type: "",
                type: "",
                office_address: "",
                telephone: "",
                mobile_number: "",
                email_address: ""
            },

            updateProvince(id)
            {
                let provinceName = "-"; // Default value in case no match is found

                for (let i = 0; i < this.provinces.length; i++) {
                    let province = this.provinces[i]; // Get the current province object

                    // Convert both to the same type for a reliable comparison
                    if (province.id == id) {
                        provinceName = province.name; // Assign the matched name
                        break; // Stop looping once a match is found
                    }
                }

                return provinceName
            },

            updateRegion(id)
            {
                let regionName = "-"; // Default value in case no match is found

                for (let i = 0; i < this.regions.length; i++) {
                    let region = this.regions[i]; // Get the current province object

                    // Convert both to the same type for a reliable comparison
                    if (region.id == id) {
                        regionName = region.name; // Assign the matched name
                        break; // Stop looping once a match is found
                    }
                }

                return regionName
            },

            updateLguType()
            {
                console.log('updating lgu type')
                let selectedLguType = this.lgu_types.find(f => f.id === this.newLgu.lgu_type);
                this.newLgu.lgu_type = selectedLguType ? selectedLguType.name : "";
            },

            async fetchLgus() {
                try {
                    const response = await fetch("{{ route('api-lgu-get') }}");
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
                this.validationErrors = [];
                try {

                    const response = await fetch("{{ route('api-lgu-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newLgu),
                    });

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errorData = await response.json();
                            this.validationErrors = Object.values(errorData.errors).flat();
                        } else {
                            throw new Error("Failed to add profile");
                        }
                        return;
                    }

                    const addedLgu = await response.json();

                    let lguUpdated = {
                        ...addedLgu.lgu,
                        region: this.updateRegion(addedLgu.lgu.region_id),
                        province: this.updateProvince(addedLgu.lgu.province_id)
                    };

                    this.lgus.push(lguUpdated);

                    this.showModal = false; // Close modal
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        const errorData = await error.response.json();
                        this.validationErrors = Object.values(errorData.errors).flat();
                    } else {
                        console.error("Error adding profile:", error);
                    }
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
                this.validationErrors = [];
                try {

                    const response = await fetch(
                        '{{ route("api-lgu-get")}}/' + this.newLgu.id, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newLgu),
                        });

                    if (!response.ok) {
                        if (response.status === 422) {
                            const errorData = await response.json();
                            this.validationErrors = Object.values(errorData.errors).flat();
                        } else {
                            throw new Error("Failed to update profile");
                        }
                        return;
                    }

                    // Find and update the profile in the list
                    const index = this.lgus.findIndex(lgu => lgu.id === this.newLgu.id);
                    if (index !== -1) this.lgus[index] = {
                        ...this.newLgu
                    };

                    this.showModal = false; // Close modal
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        const errorData = await error.response.json();
                        this.validationErrors = Object.values(errorData.errors).flat();
                    } else {
                        console.error("Error updating profile:", error);
                    }
                }
            },

            async deleteLgu(lguId) {
                if (!confirm("Are you sure you want to delete this profile?")) return;

                try {
                    const response = await fetch(`{{ route("api-lgu-get")}}/${lguId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete profile");

                    this.lgus = this.lgus.filter(lgu => lgu.id !==
                    lguId); // Remove profile from table
                } catch (error) {
                    console.error("Error deleting profile:", error);
                }
            },

            //-- for pagination
            perPage: 10,
            currentPage: 1,
            get paginatedLgus() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.lgus.slice(start, end);
            },
            get totalPages() {
                return Math.ceil(this.lgus.length / this.perPage);
            },
            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },
            init() {
                this.fetchLgus();
            },
            //-- end for pagination
        }));
    });
</script>
