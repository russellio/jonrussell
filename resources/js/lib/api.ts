const HEADERS = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
} as const;

export async function get<T>(url: string): Promise<T> {
    const response = await fetch(url, { headers: HEADERS });
    if (!response.ok) {
        throw new Error(`${response.status} ${response.statusText}`);
    }
    return response.json() as Promise<T>;
}
