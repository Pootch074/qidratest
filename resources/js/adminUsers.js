document.addEventListener("DOMContentLoaded", () => {
    // ===============================
    // Modal Logic
    // ===============================
    const modal = document.getElementById("addUserModal");
    const openBtn = document.getElementById("openAddUserModal");
    const closeBtn = document.getElementById("closeAddUserModal");
    const cancelBtn = document.getElementById("cancelAddUser");
    const form = document.getElementById("addUserForm");

    const openModal = () => {
        modal.classList.remove("hidden");
        setTimeout(
            () => modal.firstElementChild.classList.remove("scale-95"),
            10,
        );
    };

    const closeModal = () => {
        modal.firstElementChild.classList.add("scale-95");
        setTimeout(() => modal.classList.add("hidden"), 200);
        form.reset();
    };

    openBtn?.addEventListener("click", openModal);
    closeBtn?.addEventListener("click", closeModal);
    cancelBtn?.addEventListener("click", closeModal);

    // ===============================
    // Add User Form
    // ===============================
    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nameRegex = /^[A-Za-z\s'-]+$/; // Allow letters, spaces, hyphens, apostrophes

        if (!nameRegex.test(form.first_name.value.trim())) {
            alert(
                "First name must contain only letters, spaces, apostrophes, or hyphens.",
            );
            return;
        }

        if (!nameRegex.test(form.last_name.value.trim())) {
            alert(
                "Last name must contain only letters, spaces, apostrophes, or hyphens.",
            );
            return;
        }
        const data = {
            first_name: form.first_name.value.trim(),
            last_name: form.last_name.value.trim(),
            email: form.email.value.trim(),
            position: form.position.value.trim(),
            assigned_category: form.assigned_category.value,
            step_id: form.step_id.value,
            window_id: form.window_id.value,
            password: form.password.value.trim(),
        };

        if (!data.password || data.password.trim() === "") {
            alert("Password cannot be empty!");
            return;
        }

        try {
            const res = await fetch(window.appBaseUrl + "/admin/users/store", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                let msg = json.message || "Unknown error";
                if (json.errors) {
                    msg += "\n" + Object.values(json.errors).flat().join("\n");
                }
                alert(msg);
                return;
            }

            // fetchUsers();
            closeModal();
        } catch (err) {
            console.error("Add user failed:", err);
            alert("Add user failed. Check console for details.");
        }
    });

    // ===============================
    // User Type Toggle Fields
    // ===============================
    const userTypeSelect = document.querySelector('select[name="user_type"]');
    if (userTypeSelect) {
        const assignedCategory = document.querySelector(
            'select[name="assigned_category"]',
        );
        const stepSelect = document.querySelector('select[name="step_id"]');
        const windowSelect = document.querySelector('select[name="window_id"]');

        const toggleFields = () => {
            const selectedText =
                userTypeSelect.options[
                    userTypeSelect.selectedIndex
                ].text.toLowerCase();
            const isDisplay = selectedText === "display";

            assignedCategory.disabled = isDisplay;
            stepSelect.disabled = isDisplay;
            windowSelect.disabled = isDisplay;

            [assignedCategory, stepSelect, windowSelect].forEach((field) => {
                field.classList.toggle("bg-gray-100", isDisplay);
                field.classList.toggle("cursor-not-allowed", isDisplay);
            });
        };

        userTypeSelect.addEventListener("change", toggleFields);
        toggleFields();
    }

    // ===============================
    // Step → Window Dropdown
    // ===============================
    const stepSelect = document.querySelector('select[name="step_id"]');
    const windowSelect = document.querySelector('select[name="window_id"]');

    stepSelect?.addEventListener("change", function () {
        const stepId = this.value;

        windowSelect.innerHTML =
            '<option value="">-- Select Window --</option>';
        windowSelect.disabled = true;

        if (stepId) {
            fetch(`${window.appBaseUrl}/windows/by-step/${stepId}`)
                .then((res) => res.json())
                .then((data) => {
                    if (data.length > 0) {
                        data.forEach((w) => {
                            const opt = document.createElement("option");
                            opt.value = w.id;
                            opt.textContent = w.window_number;
                            windowSelect.appendChild(opt);
                        });
                        windowSelect.disabled = false;
                    }
                })
                .catch((err) => console.error("Failed to fetch windows:", err));
        }
    });

    // ===============================
    // Step → Assign Category Logic
    // ===============================
    const stepSelect2 = document.querySelector('select[name="step_id"]');
    const categoryWrapper = document.getElementById("assignCategoryWrapper");
    const categorySelect = document.getElementById("assignedCategorySelect");
    const categoryHidden = document.getElementById("assignedCategoryHidden");
    const bothOption = categorySelect?.querySelector('option[value="both"]');

    function updateCategoryVisibility() {
        if (
            !stepSelect2 ||
            !categoryWrapper ||
            !categorySelect ||
            !categoryHidden
        )
            return;

        const selectedText =
            stepSelect2.options[stepSelect2.selectedIndex]?.text || "";
        const stepNumber = parseInt(selectedText.split("-")[0]?.trim()) || null;

        const isStep3or4 = stepNumber === 3 || stepNumber === 4;

        // Hide dropdown entirely for step 3 or 4 → auto-assign "both"
        if (isStep3or4) {
            categoryWrapper.style.display = "none";
            categorySelect.removeAttribute("name");
            categorySelect.required = false;
            categorySelect.disabled = true;

            categoryHidden.value = "both";
            categoryHidden.setAttribute("name", "assigned_category");
        } else {
            // Show dropdown normally
            categoryWrapper.style.display = "";
            categorySelect.setAttribute("name", "assigned_category");
            categorySelect.required = true;
            categorySelect.disabled = false;

            categoryHidden.removeAttribute("name");
        }

        // ✅ Extra Rule: Hide "Both" option when step_number = 1 or 2
        if (stepNumber === 1 || stepNumber === 2) {
            if (bothOption) {
                bothOption.style.display = "none";
                if (categorySelect.value === "both") {
                    categorySelect.value = ""; // reset if user had picked "Both"
                }
            }
        } else {
            if (bothOption) {
                bothOption.style.display = "block";
            }
        }
    }

    // Run only if wrapper exists
    if (stepSelect2 && categoryWrapper) {
        updateCategoryVisibility();
        stepSelect2.addEventListener("change", updateCategoryVisibility);
    }
});

// ===============================
// Password Eye Toggle
// ===============================
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");
const eyeIcon = document.getElementById("eyeIcon");

togglePassword?.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;

    eyeIcon.innerHTML =
        type === "password"
            ? '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10z" /><path d="M10 7a3 3 0 100 6 3 3 0 000-6z" />'
            : '<path d="M3.707 3.707a1 1 0 00-1.414 1.414l1.095 1.094C2.52 7.083 1.732 8.462 1 10c.73 2.89 4 7 9 7 1.605 0 3.123-.483 4.414-1.293l1.879 1.879a1 1 0 001.414-1.414l-14-14zM10 5a5 5 0 014.546 3.032l-1.479 1.478A3 3 0 0010 7a3 3 0 00-1.667.516L7.044 7.03A5 5 0 0110 5z"/>';
});

// ===============================
// Delete User (MAKE IT GLOBAL)
// ===============================
window.deleteUser = function (userId) {
    if (
        !confirm(
            "Disapproving this user will permanently delete them and cannot be undone. Are you sure you want to proceed?",
        )
    )
        return;
    fetch(`${window.appBaseUrl}/admin/active-users/${userId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                document.getElementById(`userRow-${userId}`)?.remove();
            } else {
                alert(
                    "Error deleting user: " + (data.message ?? "Unknown error"),
                );
            }
        })
        .catch((err) => {
            console.error("Delete user failed:", err);
            alert("An error occurred while deleting the user.");
        });
};
