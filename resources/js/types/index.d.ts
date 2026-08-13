export interface Auth {
    user: Pick<User, 'id' | 'name'> | null;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
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
    description?: string | null;
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

export interface ApiResponse<T> {
    success: boolean;
    data: T;
}

export interface Skill {
    id: number;
    name: string;
}

export interface SkillType {
    id: number;
    name: string;
    slug: string;
    skills: Skill[];
}

export interface TechStackItem {
    tech: string;
    percent: string;
    iconType: string;
    iconName: string;
    active?: boolean;
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

export interface PositionSkill {
    id: number;
    name: string;
}

/** A single role as returned by GET /api/timeline (PositionResource). */
export interface TimelinePosition {
    id: number;
    title: string;
    description: string | null; // sanitized HTML (lead + bullet list) or null
    startDate: string; // Y-m-d
    endDate: string | null; // Y-m-d, null when current
    months: number;
    isCurrent: boolean;
    company: Company | null;
    skills: PositionSkill[];
}

export interface PostImage {
    src: string;
    alt: string;
}

/** A post as returned by GET /api/posts (PostResource). */
export interface Post {
    id: string; // slug
    title: string;
    excerpt: string | null;
    year: string | null;
    publishedAt: string | null;
    image: PostImage | null;
    externalUrl: string | null;
    hasBody: boolean;
}
