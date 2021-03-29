import { makeStyles } from '@material-ui/core/styles'
import Table from '@material-ui/core/Table'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableContainer from '@material-ui/core/TableContainer'
import TableHead from '@material-ui/core/TableHead'
import TableRow from '@material-ui/core/TableRow'
import TablePagination from '@material-ui/core/TablePagination'
import Paper from '@material-ui/core/Paper'

const useStyles = makeStyles({
  table: {
    minWidth: 650,
  },
})

export interface Column {
  key: string
  label: string
  align?: 'left' | 'center' | 'right' | 'justify'
}

interface Props {
  columns: Column[]
  rows: any[]
  rowsPerPage: number
  page: number
  onPageChange: () => void
  onRowsPerPageChange: (event: any) => void
}

export default function DataTable({
  columns,
  rows,
  rowsPerPage,
  page,
  onPageChange,
  onRowsPerPageChange,
}: Props) {
  const classes = useStyles()

  return (
    <>
      <TableContainer component={Paper}>
        <Table className={classes.table} aria-label="table">
          <TableHead>
            <TableRow>
              {columns.map((column) => (
                <TableCell
                  key={`table-column-${column.key}`}
                  align={column.align || 'left'}
                >
                  {column.label}
                </TableCell>
              ))}
            </TableRow>
          </TableHead>
          <TableBody>
            {rows.map((row) => (
              <TableRow key={`table-row-${row.id}`}>
                {columns.map((column) => (
                  <TableCell
                    key={`table-row-${row.id}-cell-${column.key}`}
                    align={column.align || 'left'}
                  >
                    {row[column.key]}
                  </TableCell>
                ))}
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
      <TablePagination
        rowsPerPageOptions={[5, 10, 25]}
        component="div"
        count={rows.length}
        rowsPerPage={rowsPerPage}
        page={page}
        onChangePage={onPageChange}
        onChangeRowsPerPage={onRowsPerPageChange}
      />
    </>
  )
}
