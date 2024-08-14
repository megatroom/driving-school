'use server';

import { getEnvVars } from './config';

const BACKGROUND_REVALIDATE = 3600; // 1 hour
const UNPLASH_RANDOM_CARS_IMAGE =
  'https://api.unsplash.com/photos/random?query=cars&client_id=';

export async function getRandomImage(): Promise<string> {
  const backgroundUrl = `${UNPLASH_RANDOM_CARS_IMAGE}${getEnvVars().unplash.accessKey}`;

  const response = await fetch(backgroundUrl, {
    next: { revalidate: BACKGROUND_REVALIDATE },
  }).then((res) => res.json());

  return response.urls.raw;
}
