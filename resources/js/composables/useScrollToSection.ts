export function useScrollToSection() {
    const scrollToSection = (section: string) => {
        const container = document.getElementById(section) ?? document.getElementById('home');
        if (!container) return;
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };

    return { scrollToSection };
}
