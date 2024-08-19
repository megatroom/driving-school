export interface Vouchers {
  id: number;
  employee: {
    id: number;
    name: string;
  };
  createdAt: Date;
  value: number;
  reason: string;
}
