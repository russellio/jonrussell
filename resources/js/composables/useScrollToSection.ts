export function useScrollToSection() {
    const scrollToSection = (section: string, block: ScrollLogicalPosition = 'start') => {
        const container = document.getElementById(section) ?? document.getElementById('home');
        if (!container) return;
        container.scrollIntoView({ behavior: 'smooth', block });
    };

    return { scrollToSection };
}
