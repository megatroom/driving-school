export type FormState<Model> =
  | {
      errors?: Partial<Record<keyof Model, string[] | undefined>>;
      message?: string;
    }
  | undefined;

export interface IBaseForm<Values> {
  onChange?: (e: React.ChangeEvent<any>) => void;
  onBlur?: (e: React.FocusEvent<any>) => void;
  values: Values;
}
