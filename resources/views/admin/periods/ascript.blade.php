<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("aTable", () => ({
            search: '',
            assessment: [],
            showModal: false,
            editMode: false,
            newA: {
                id: null,
                name: "",
                start_date: "",
                end_date: "",
                status: "ongoing" // ongoing, completed
            },

            async fetchP() {
                try {
                    const response = await fetch("{{ route('api-assessment-get') }}");
                    this.assessment = await response.json();
                } catch (error) {
                    console.error("Error fetching assessment:", error);
                }
            },

            openModal(isEdit) {
                this.editMode = isEdit;
                this.showModal = true;

                if (!isEdit) {
                    this.newA = {
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
                    const response = await fetch("{{ route('api-assessment-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newA),
                    });

                    if (!response.ok) throw new Error("Failed to add assessment");

                    const addedA = await response.json();
                    this.assessment.push(addedA.assessment); // Append new assessment to table
                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error adding assessment:", error);
                }
            },

            editP(assessment) {
                this.openModal(true);
                this.newA = {
                    ...assessment
                }; // Load selected assessment into form

                this.editMode = true;
                this.showModal = true;
            },

            async updateA() {
                try {

                    const response = await fetch(
                        "{{ route('api-assessment-put', ':id') }}".replace(':id', this.newA.id), {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newA),
                        });

                    if (!response.ok) throw new Error("Failed to update assessment");

                    const index = this.assessment.findIndex(a => a.id === this.newA.id);
                    if (index !== -1) this.assessment[index] = {
                        ...this.newA
                    };

                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error updating assessment:", error);
                }
            },

            async deleteP(aId) {
                if (!confirm("Are you sure you want to delete this assessment?")) return;

                try {
                    const response = await fetch(`{{ url('api/assessment')  }}/${aId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete assessment");

                    this.assessment = this.assessment.filter(a => a.id !==
                    aId); // Remove assessment from table
                } catch (error) {
                    console.error("Error deleting assessment:", error);
                }
            },

            //-- for pagination and search
            get filteredP() {
                if (!this.assessment || this.assessment.length === 0) return []; // Prevent errors if assessment are empty

                return this.assessment.filter(a =>
                    Object.values(a).some(value => {
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
