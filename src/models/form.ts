export type FormState<Model> =
  | {
      success: boolean;
      errors?: Partial<Record<keyof Model, string[] | undefined>>;
      message?: string;
      affectedRows?: number;
      insertId?: number;
    }
  | undefined;

export interface IBaseForm<Values> {
  onChange?: (e: React.ChangeEvent<any>) => void;
  onBlur?: (e: React.FocusEvent<any>) => void;
  values: Values;
}
