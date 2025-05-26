<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("pTable", () => ({
            search: '',
            periods: [],
            showModal: false,
            editMode: false,
            newP: {
                id: null,
                name: "",
                start_date: "",
                end_date: "",
                status: "ongoing" // ongoing, completed
            },

            async fetchP() {
                try {
                    const response = await fetch("{{ route('api-periods-get') }}");
                    this.periods = await response.json();
                } catch (error) {
                    console.error("Error fetching assessment periods:", error);
                }
            },

            openModal(isEdit) {
                this.editMode = isEdit;
                this.showModal = true;

                if (!isEdit) {
                    this.newP = {
                        id: null,
                        name: "",
                        start_date: "",
                        end_date: "",
                        status: "Ongoing" // Ongoing, Completed
                    };
                }
            },

            async addP() {
                try {
                    const response = await fetch("{{ route('api-periods-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newP),
                    });

                    if (!response.ok) throw new Error("Failed to add assessment period");

                    const addedP = await response.json();
                    this.periods.push(addedP.period); // Append new period to table
                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error adding period:", error);
                }
            },

            editP(period) {
                this.openModal(true);
                this.newP = {
                    ...period
                }; // Load selected period into form

                this.editMode = true;
                this.showModal = true;
            },

            async updateP() {
                try {

                    const response = await fetch(
                        "{{ route('api-periods-put', ':id') }}".replace(':id', this.newP.id), {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newP),
                        });

                    if (!response.ok) throw new Error("Failed to update period");

                    const index = this.periods.findIndex(p => p.id === this.newP.id);
                    if (index !== -1) this.periods[index] = {
                        ...this.newP
                    };

                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error updating period:", error);
                }
            },

            async deleteP(pId) {
                if (!confirm("Are you sure you want to delete this period?")) return;

                try {
                    const response = await fetch(`{{ url('api/periods')  }}/${pId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete assessment period");

                    this.periods = this.periods.filter(p => p.id !==
                    pId); // Remove period from table
                } catch (error) {
                    console.error("Error deleting assessment period:", error);
                }
            },

            //-- for pagination and search
            get filteredP() {
                if (!this.periods || this.periods.length === 0) return []; // Prevent errors if periods are empty

                return this.periods.filter(p =>
                    Object.values(p).some(value => {
                        if (value === null || value === undefined) return false; // Skip null/undefined values
                        return String(value).toLowerCase().includes(this.search.toLowerCase());
                    })
                );
            },

            perPage: 10,
            currentPage: 1,

            get paginateP() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;

                return this.filteredP.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.filteredP.length / this.perPage);
            },

            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },

            init() {
                this.fetchP();
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
