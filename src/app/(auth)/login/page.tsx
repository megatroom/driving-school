import React from 'react';
import { LoginPage } from '../../../components/pages/auth/LoginPage';

const UNPLASH_RANDOM_CARS_IMAGE =
  'https://api.unsplash.com/photos/random?query=cars&client_id=';
const BACKGROUND_REVALIDATE = 3600; // 1 hour

export default async function Login() {
  const backgroundUrl = `${UNPLASH_RANDOM_CARS_IMAGE}${process.env.UNPLASH_ACCESS_KEY}`;
  const unplash = await fetch(backgroundUrl, {
    next: { revalidate: BACKGROUND_REVALIDATE },
  }).then((res) => res.json());

  return <LoginPage backgroundImageUrl={unplash.urls.raw} />;
}
