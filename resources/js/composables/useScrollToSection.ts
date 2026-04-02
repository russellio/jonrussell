import { ref } from 'vue';

export function useScrollToSection() {
    const mobileMenuOpen = ref(false);

    const scrollToSection = (section: string) => {
        const container = document.getElementById(section) ?? document.getElementById('home');

        if (!container) {
            return;
        }

        container.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
        });

        mobileMenuOpen.value = false;
    };

    const isMobileMenuOpen = () => mobileMenuOpen.value;

    const toggleMobileMenu = () => {
        mobileMenuOpen.value = !mobileMenuOpen.value;
    };

    return {
        scrollToSection,
        isMobileMenuOpen,
        toggleMobileMenu,
    };
}
