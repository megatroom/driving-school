import { Button } from '@/components/atoms/forms/Button';
import { InputPassword } from '@/components/atoms/forms/InputPassword';
import { InputText } from '@/components/atoms/forms/InputText';
import { AuthTemplate } from '@/components/templates/AuthTemplate';
import { LoginForm } from '@/models/auth';
import { FormState, IBaseForm } from '@/models/form';
import { Alert, AlertDescription, AlertIcon } from '@chakra-ui/react';

interface LoginPageProps extends IBaseForm<LoginForm> {
  backgroundImageUrl?: string;
  formState: FormState<LoginForm>;
  pending: boolean;
}

export function LoginPage({
  onChange,
  onBlur,
  values,
  backgroundImageUrl,
  formState,
  pending,
}: LoginPageProps) {
  return (
    <AuthTemplate
      title="Login"
      backgroundImageUrl={backgroundImageUrl}
      renderFooter={() => (
        <Button loading={pending} loadingText="Enviando..." type="submit">
          Entrar
        </Button>
      )}
    >
      {formState?.message && (
        <Alert status="error" mb={6}>
          <AlertIcon />
          <AlertDescription>{formState?.message}</AlertDescription>
        </Alert>
      )}
      <InputText
        label="Usuário"
        name="username"
        errors={formState?.errors?.username}
        onChange={onChange}
        onBlur={onBlur}
        value={values.username}
      />
      <InputPassword
        label="Senha"
        name="password"
        errors={formState?.errors?.password}
        onChange={onChange}
        onBlur={onBlur}
        value={values.password}
      />
    </AuthTemplate>
  );
}
