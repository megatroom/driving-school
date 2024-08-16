import 'server-only';

import { cookies } from 'next/headers';
import { JWTPayload, SignJWT, jwtVerify } from 'jose';

const secretKey = process.env.SESSION_SECRET;
const encodedKey = new TextEncoder().encode(secretKey);

const ALGORITHM_CODE = 'HS256';
const DAYS_TO_SESSION_EXPIRE = 6;
const USER_SESSION_COOKIE_KEY = 'session';

export interface UserSession extends JWTPayload {
  id: number;
  name: string;
  gender?: string;
}

async function encrypt(userSession: UserSession) {
  return new SignJWT(userSession)
    .setProtectedHeader({ alg: ALGORITHM_CODE })
    .setIssuedAt()
    .setExpirationTime(`${DAYS_TO_SESSION_EXPIRE}d`)
    .sign(encodedKey);
}

async function decrypt(
  session: string | undefined = '',
): Promise<UserSession | null> {
  try {
    const { payload } = await jwtVerify<UserSession>(session, encodedKey, {
      algorithms: [ALGORITHM_CODE],
    });
    return payload;
  } catch (error) {
    console.error('Failed to verify session');
    return null;
  }
}

export async function createSession(userSession: UserSession) {
  const expiresAt = new Date(
    Date.now() + DAYS_TO_SESSION_EXPIRE * 24 * 60 * 60 * 1000,
  );
  const session = await encrypt(userSession);

  cookies().set(USER_SESSION_COOKIE_KEY, session, {
    httpOnly: true,
    secure: true,
    expires: expiresAt,
    sameSite: 'lax',
    path: '/',
  });
}

export async function getSession(): Promise<UserSession | null> {
  const session = cookies().get(USER_SESSION_COOKIE_KEY)?.value;
  const userSession = await decrypt(session);

  if (!session || !userSession) {
    return null;
  }

  return userSession;
}

export async function getUserSession(): Promise<UserSession> {
  const userSession = await getSession();

  if (!userSession) {
    throw new Error('User not found.');
  }

  return userSession;
}

export async function deleteSession(): Promise<void> {
  cookies().delete(USER_SESSION_COOKIE_KEY);
}
