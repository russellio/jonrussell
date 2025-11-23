import { ref } from 'vue';

export function useScrollToSection() {
  const mobileMenuOpen = ref(false);

  const scrollToSection = (section: string) => {
    let container = document.getElementById(section);

    if (!container) {
      console.log("Element ID '" + section + "' was not found.");
      container = document.getElementById('home');
    }

    container.scrollIntoView({
      behavior: 'smooth',
      block: 'nearest'
    });

    mobileMenuOpen.value = false;
  };

  const isMobileMenuOpen = () => {
    return mobileMenuOpen.value;
  }

  const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
  }

  return {
    scrollToSection,
    isMobileMenuOpen,
    toggleMobileMenu
  };
}
