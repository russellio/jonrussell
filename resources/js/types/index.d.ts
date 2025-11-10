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

export interface Project {
    id: string;
    title: string;
    byline: string;
    keyTakeaways: string[];
    description: string;
    highlightedSkills: string[];
    skills: string[];
    technologies: string[];
    tools: string[];
    company: Company;
    primaryImage: Image;
    bgImage: string;
    bgPositionX: string;
    bgPositionY: string;
    images?: Image[];
    links: ProjectLink[];
    awards?: string[];
}
