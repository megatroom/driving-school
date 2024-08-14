import {
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  Heading,
} from '@chakra-ui/react';
import { ReactNode } from 'react';
import { BackgroundImage } from '../atoms/surfaces/BackgroundImage';

interface AuthTemplateProps {
  title: string;
  children: ReactNode;
  backgroundImageUrl?: string;
  renderFooter?: () => ReactNode;
}

export function AuthTemplate({
  backgroundImageUrl,
  title,
  children,
  renderFooter,
}: AuthTemplateProps) {
  return (
    <BackgroundImage backgroundImageUrl={backgroundImageUrl}>
      <Card size="lg">
        <CardHeader>
          <Heading size="md">{title}</Heading>
        </CardHeader>
        <CardBody paddingTop={0} paddingBottom={0}>
          {children}
        </CardBody>
        <CardFooter justifyContent="flex-end">{renderFooter?.()}</CardFooter>
      </Card>
    </BackgroundImage>
  );
}
