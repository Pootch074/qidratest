<script>
    // This ensures the code inside only runs after the HTML page is fully loaded. Without this, getElementById might fail if the elements aren’t loaded yet.
    document.addEventListener('DOMContentLoaded', function() {

        // divisionSelect → the dropdown where the user selects a division. sectionSelect → the dropdown where sections (units under that division) will appear.
        const divisionSelect = document.getElementById('division_id');
        const sectionSelect = document.getElementById('section_id');

        // When the user selects a division, this event triggers. this.value is the division_id of the selected division.
        divisionSelect.addEventListener('change', function() {
            const divisionId = this.value;

            // Immediately disables the sections dropdown so the user cannot interact with it while the data is being fetched. Shows a placeholder option: "Loading...".
            sectionSelect.disabled = true;
            sectionSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

            // Sends a GET request to /auth/sections/{divisionId}. Expects a JSON response with an array of sections.
            Example response: [{
                    "id": 1,
                    "section_name": "HR"
                },
                {
                    "id": 2,
                    "section_name": "Finance"
                }
            ]
            fetch(`/auth/sections/${divisionId}`)
                .then(response => response.json())
                .then(sections => {


                    // Re-enables the section dropdown. Resets the first placeholder to "Section/Unit".
                    sectionSelect.disabled = false;
                    sectionSelect.innerHTML =
                        '<option value="" disabled selected>Section/Unit</option>';


                    // If there are no sections, it shows "No sections available".
                    if (sections.length === 0) {
                        sectionSelect.innerHTML +=
                            '<option value="">No sections available</option>';
                        return; // Exit the function
                    }

                    // Loop through each section and add it as an option
                    sections.forEach(section => {
                        // Create a new option element
                        const option = document.createElement(
                            'option');
                        // Set the value for form submission
                        option.value = section.id;
                        // Set the visible text
                        option.textContent = section.section_name;
                        // Add the option to the dropdown
                        sectionSelect.appendChild(option);
                    });
                })


            // If the request fails (network error, server error, etc.): 
            Re - enables the dropdown.Shows "Failed to load sections
                .catch(() => {
                    sectionSelect.disabled = false;
                    sectionSelect.innerHTML =
                        '<option value="">Failed to load sections</option>';
                });
        });
    });
</script>
