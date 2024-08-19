'use client';

import React, { useEffect, useState } from 'react';
import { authenticate } from '@/services/auth';

import { LoginPage } from '@/components/pages/auth/LoginPage';
import { getRandomImage } from '@/helpers/unplash';
import { Form, Formik, FormikHelpers } from 'formik';
import { FormState } from '@/models/form';
import { LoginForm } from '@/models/auth';

export default function Login() {
  const [backgroundUrl, setBackgroundUrl] = useState<string | undefined>();
  const [formState, setFormState] = useState<FormState<LoginForm>>();

  useEffect(() => {
    getRandomImage()
      .then((url) => {
        setBackgroundUrl(url);
      })
      .catch((error) => {
        console.error(error);
      });
  });

  const handleSubmit = (
    values: LoginForm,
    { setSubmitting }: FormikHelpers<LoginForm>,
  ) => {
    authenticate(values)
      .then((result) => {
        if (!result?.success) {
          setSubmitting(false);
          setFormState(result);
        }
      })
      .catch(console.error);
  };

  return (
    <Formik
      initialValues={{ username: '', password: '' }}
      onSubmit={handleSubmit}
    >
      {({ values, handleChange, handleBlur, isSubmitting }) => (
        <Form>
          <LoginPage
            backgroundImageUrl={backgroundUrl}
            formState={formState}
            onChange={handleChange}
            onBlur={handleBlur}
            pending={isSubmitting}
            values={values}
          />
        </Form>
      )}
    </Formik>
  );
}
