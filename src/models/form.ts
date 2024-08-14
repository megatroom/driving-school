export type FormState<Model> =
  | {
      errors?: Record<keyof Model, string[]>;
      message?: string;
    }
  | undefined;

export interface IBaseForm<Values> {
  onChange?: (e: React.ChangeEvent<any>) => void;
  onBlur?: (e: React.FocusEvent<any>) => void;
  values: Values;
}
