export interface Auth {
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Image {
    src: string;
    alt: string;
    title: string;
}

export interface CompanyLogo {
    src: string;
    alt: string;
    displayName: boolean;
}

export interface Company {
    name: string;
    logo: CompanyLogo;
    link?: string;
}

export interface ProjectLink {
    title: string;
    url: string;
}

export interface Technology {
    name: string;
    iconType?: string;
    iconName?: string;
}

export interface Tool {
    name: string;
    iconType?: string;
    iconName?: string;
}

export interface Project {
    id: string;
    title: string;
    byline: string;
    keyTakeaways: string[];
    description: string;
    highlightedSkills: string[];
    skills: string[];
    technologies: Technology[];
    tools: Tool[];
    company: Company | null;
    primaryImage: Image | null;
    bgImage: string | null;
    bgPositionX: string | null;
    bgPositionY: string | null;
    images?: Image[];
    links: ProjectLink[];
    awards?: string[];
}
