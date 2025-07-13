import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import axios from "axios";

export const useSidebarStore = defineStore("sidebar", () => {
    const parent_id = ref(null);
    const child_id = ref(null);
    const grandchild_id = ref(null);
    const childCategories = reactive([]);
    const grandchildCategories = reactive([]);

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

    function clearSelections() {
        parent_id.value = null;
        child_id.value = null;
        grandchild_id.value = null;
        childCategories.splice(0, childCategories.length);
        grandchildCategories.splice(0, grandchildCategories.length);
    }

    return {
        parent_id,
        child_id,
        grandchild_id,
        childCategories,
        grandchildCategories,
        fetchSubcategories,
        clearSelections,
    };
});
