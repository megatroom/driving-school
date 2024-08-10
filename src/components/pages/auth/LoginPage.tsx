import { Button } from '@/components/atoms/forms/Button';
import { InputPassword } from '@/components/atoms/forms/InputPassword';
import { InputText } from '@/components/atoms/forms/InputText';
import { AuthTemplate } from '@/components/templates/AuthTemplate';

interface LoginPageProps {
  backgroundImageUrl?: string;
}

export function LoginPage({ backgroundImageUrl }: LoginPageProps) {
  return (
    <AuthTemplate
      title="Login"
      backgroundImageUrl={backgroundImageUrl}
      renderFooter={() => <Button>Entrar</Button>}
    >
      <InputText label="Usuário" />
      <InputPassword label="Senha" />
    </AuthTemplate>
  );
}
