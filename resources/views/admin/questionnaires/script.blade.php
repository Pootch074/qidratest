<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("qTable", () => ({
            search: '',
            questionnaires: [],
            showModal: false,
            editMode: false,
            newQ: {
                id: null,
                questionnaire_name: "",
                effectivity_date: "",
                status: "Unpublished" // Published, Ended
            },

            async fetchQ() {
                try {
                    const response = await fetch("{{ route('api-questionnaires-get') }}");
                    this.questionnaires = await response.json();
                } catch (error) {
                    console.error("Error fetching questionnaires:", error);
                }
            },

            openModal(isEdit) {
                this.editMode = isEdit;
                this.showModal = true;

                if (!isEdit) {
                    this.newQ = {
                        id: null,
                        questionnaire_name: "",
                        effectivity_date: "",
                        status: "Unpublished" // Published, Ended
                    };
                }
            },

            async addQ() {
                try {
                    const response = await fetch("{{ route('api-questionnaires-post') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(this.newQ),
                    });

                    if (!response.ok) throw new Error("Failed to add questionnaire");

                    const addedQ = await response.json();
                    this.questionnaires.push(addedQ.questionnaire); // Append new questionnaire to table
                    this.showModal = false; // Close modal

                    location.reload();
                } catch (error) {
                    console.error("Error adding questionnaire:", error);
                }
            },

            editQ(questionnaire) {
                this.openModal(true);
                this.newQ = {
                    ...questionnaire
                }; // Load selected questionnaire into form

                this.editMode = true;
                this.showModal = true;
            },

            async updateQ() {
                try {

                    const response = await fetch(
                        "{{ route('api-questionnaires-put', ':id') }}".replace(':id', this.newQ.id), {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(this.newQ),
                        });

                    if (!response.ok) throw new Error("Failed to update questionnaire");

                    const index = this.questionnaires.findIndex(q => q.id === this.newQ.id);
                    if (index !== -1) this.questionnaires[index] = {
                        ...this.newQ
                    };

                    this.showModal = false; // Close modal
                } catch (error) {
                    console.error("Error updating questionnaire:", error);
                }
            },

            async deleteQ(qId) {
                if (!confirm("Are you sure you want to delete this questionnaire?")) return;

                try {
                    const response = await fetch(`{{ url('api/questionnaires')  }}/${qId}`, {
                        method: "DELETE",
                    });

                    if (!response.ok) throw new Error("Failed to delete questionnaire");

                    this.questionnaires = this.questionnaires.filter(q => q.id !==
                    qId); // Remove questionnaire from table
                } catch (error) {
                    console.error("Error deleting questionnaire:", error);
                }
            },

            //-- for pagination and search
            get filteredQ() {
                if (!this.questionnaires || this.questionnaires.length === 0) return []; // Prevent errors if questionnaires are empty

                return this.questionnaires.filter(q =>
                    Object.values(q).some(value => {
                        if (value === null || value === undefined) return false; // Skip null/undefined values
                        return String(value).toLowerCase().includes(this.search.toLowerCase());
                    })
                );
            },

            perPage: 10,
            currentPage: 1,

            get paginateQ() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;

                return this.filteredQ.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.filteredQ.length / this.perPage);
            },

            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },

            init() {
                this.fetchQ();
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
