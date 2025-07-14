import { defineStore } from "pinia";
import { ref, reactive, computed } from "vue";
import axios from "axios";

export const useStore = defineStore("sidebar", () => {
    // State for search term
    const search = ref("");

    // State for category selections
    const parent_id = ref(null);
    const child_id = ref(null);
    const grandchild_id = ref(null);

    // Reactive arrays for subcategories
    const childCategories = reactive([]);
    const grandchildCategories = reactive([]);

    // Computed property to determine the active category ID
    const category_id = computed(() => {
        return grandchild_id.value || child_id.value || parent_id.value || null;
    });

    // Fetch subcategories for a given parent ID
    async function fetchSubcategories(parentId, targetRef) {
        try {
            if (parentId) {
                const response = await axios.get(
                    `/categories/${parentId}/subcategories`
                );
                targetRef.splice(0, targetRef.length, ...response.data);
            } else {
                targetRef.splice(0, targetRef.length);
            }
        } catch (error) {
            targetRef.splice(0, targetRef.length);
        }
    }

    // Clear all category selections and subcategory arrays
    function clearSelections() {
        parent_id.value = null;
        child_id.value = null;
        grandchild_id.value = null;
        childCategories.splice(0, childCategories.length);
        grandchildCategories.splice(0, grandchildCategories.length);
    }

    // Set the search term
    function setSearch(searchTerm) {
        search.value = searchTerm;
    }

    // Clear the search term
    function clearSearch() {
        search.value = "";
    }

    return {
        search,
        parent_id,
        child_id,
        grandchild_id,
        childCategories,
        grandchildCategories,
        category_id,
        fetchSubcategories,
        clearSelections,
        setSearch,
        clearSearch,
    };
});
