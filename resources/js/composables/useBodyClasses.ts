import { onBeforeUnmount, onMounted } from 'vue';

export function useBodyClasses(classes: string | string[]) {
    const classesArray = Array.isArray(classes) ? classes : classes.split(' ');

    onMounted(() => {
        if (typeof document !== 'undefined') {
            classesArray.forEach((className) => {
                if (className.trim()) {
                    document.body.classList.add(className.trim());
                }
            });
        }
    });

    onBeforeUnmount(() => {
        if (typeof document !== 'undefined') {
            classesArray.forEach((className) => {
                if (className.trim()) {
                    document.body.classList.remove(className.trim());
                }
            });
        }
    });
}

