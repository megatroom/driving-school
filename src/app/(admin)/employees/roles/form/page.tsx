'use client';

import { CancelButton } from '@/components/atoms/actions/CancelButton';
import { SaveButton } from '@/components/atoms/actions/SaveButton';
import { InputText } from '@/components/atoms/forms/InputText';
import { FormErrorAlert } from '@/components/molecules/FormErrorAlert';
import { PageHeading } from '@/components/molecules/PageHeading';
import {
  EmployeeRoleForm,
  EmployeeRoleFormState,
  initialEmployeeRoleForm,
} from '@/models/employees';
import { createEmployeeRole } from '@/services/employees';
import { ButtonGroup, Card, CardBody, CardFooter } from '@chakra-ui/react';
import { Form, Formik, FormikHelpers } from 'formik';
import { useRouter } from 'next/navigation';
import { useState } from 'react';

export default function EmployeesRolesFormPage() {
  const [formState, setFormState] = useState<EmployeeRoleFormState>();
  const router = useRouter();

  const handleSubmit = (
    values: EmployeeRoleForm,
    { setSubmitting }: FormikHelpers<EmployeeRoleForm>,
  ) => {
    createEmployeeRole(values)
      .then((result) => {
        if (result?.success) {
          router.push('/employees/roles');
        } else {
          setSubmitting(false);
          setFormState(result);
        }
      })
      .catch(console.error);
  };

  return (
    <>
      <PageHeading title="Nova Função" />
      <Card>
        <Formik initialValues={initialEmployeeRoleForm} onSubmit={handleSubmit}>
          {({ values, handleChange, handleBlur, isSubmitting }) => (
            <Form>
              <CardBody>
                <FormErrorAlert message={formState?.message} />
                <InputText
                  label="Descrição"
                  name="description"
                  errors={formState?.errors?.description}
                  onChange={handleChange}
                  onBlur={handleBlur}
                  value={values.description}
                  autoFocus
                />
              </CardBody>
              <CardFooter justifyContent="flex-end">
                <ButtonGroup spacing={4}>
                  <CancelButton linkTo="/employees/roles" />
                  <SaveButton loading={isSubmitting} />
                </ButtonGroup>
              </CardFooter>
            </Form>
          )}
        </Formik>
      </Card>
    </>
  );
}
