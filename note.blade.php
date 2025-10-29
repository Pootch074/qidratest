<script>
    if (normalizedName === "release") return;
    if (
        normalizedName === "assessment" &&
        window.appUser.assignedCategory.toLowerCase() === "regular"
    ) {
        return;
    }


    if (
        normalizedName === "pre-assessment" && normalizedName === "encoding" &&
        window.appUser.assignedCategory.toLowerCase() === "priority"
    ) {
        bgClass = "bg-red-600";
    }
</script>
