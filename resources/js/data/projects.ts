import type { Project } from '@/js/types';

export const projects: Project[] = [
    {
        id: '1',
        title: 'Build a Spotify Connected App',
        url: 'https://www.newline.co/courses/build-a-spotify-connected-app',
        description:
            'Video course that teaches how to build a web app with the Spotify Web API. Topics covered include the principles of REST APIs, user auth flows, Node, Express, React, Styled Components, and more.',
        image: '/images/projects/course-card.png',
        tech: [],
    },
    {
        id: '2',
        title: 'Spotify Profile',
        url: 'https://spotify-profile.herokuapp.com/',
        description:
            'Web app for visualizing personalized Spotify data. View your top artists, top tracks, recently played tracks, and detailed audio information about each track. Create and save new playlists of recommended tracks based on your existing playlists and more.',
        image: '/images/projects/spotify-profile.png',
        tech: ['React', 'Express', 'Spotify API', 'Heroku'],
    },
    {
        id: '3',
        title: 'Halcyon Theme',
        url: 'https://halcyon-theme.netlify.app/',
        description: 'Minimal dark blue theme for VS Code, Sublime Text, Atom, iTerm, and more.',
        image: '/images/projects/halcyon.png',
        tech: [],
    },
    {
        id: '4',
        title: 'brittanychiang.com (v4)',
        url: 'https://v4.brittanychiang.com/',
        description: 'An old portfolio site built with Gatsby with 6k+ stars and 3k+ forks',
        image: '/images/projects/v4.png',
        tech: ['Gatsby', 'Styled Components', 'Netlify'],
    },
];
