import { PlusButton } from '@/components/atoms/actions/PlusButton';
import { PageHeading } from '@/components/molecules/PageHeading';

export default function EmployeeRolesPage() {
  return (
    <>
      <PageHeading title="Funções">
        <PlusButton linkTo="/employees/roles/form">Nova Função</PlusButton>
      </PageHeading>
    </>
  );
}
