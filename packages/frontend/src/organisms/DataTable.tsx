import { makeStyles } from '@material-ui/core/styles'
import Table from '@material-ui/core/Table'
import TableBody from '@material-ui/core/TableBody'
import TableCell from '@material-ui/core/TableCell'
import TableContainer from '@material-ui/core/TableContainer'
import TableHead from '@material-ui/core/TableHead'
import TableRow from '@material-ui/core/TableRow'
import TablePagination from '@material-ui/core/TablePagination'
import Toolbar from '@material-ui/core/Toolbar'
import Typography from '@material-ui/core/Typography'
import Tooltip from '@material-ui/core/Tooltip'
import IconButton from '@material-ui/core/IconButton'
import Paper from '@material-ui/core/Paper'
import Skeleton from '@material-ui/lab/Skeleton'
import AddIcon from '@material-ui/icons/Add'

import { formatNumberToString } from 'formatters/NumberFormatter'

const useStyles = makeStyles((theme) => ({
  paper: {
    width: '100%',
    marginBottom: theme.spacing(2),
  },
  table: {
    minWidth: 650,
  },
  toolbar: {
    paddingLeft: theme.spacing(2),
    paddingRight: theme.spacing(1),
  },
  title: {
    flex: '1 1 100%',
  },
  bodyRow: {
    cursor: 'pointer',
  },
}))

type ColumnType = 'text' | 'currency'

export interface Column {
  key: string
  label: string
  align?: 'left' | 'center' | 'right' | 'justify'
  type?: ColumnType
}

interface Props {
  title: string
  columns: Column[]
  rows: any[]
  rowsPerPage: number
  page: number
  isLoading?: boolean
  error?: Error | null
  onPageChange: () => void
  onRowsPerPageChange: (event: any) => void
  onRowClick: (id: number) => void
  onNewClick: () => void
}

const getEmptyRows = (rowsPerPage: number) => {
  const rows = []

  for (let i = 1; i <= rowsPerPage; i++) {
    rows.push({
      id: `empty-${i}`,
    })
  }

  return rows
}

const formatValue = (type: ColumnType, value: any) => {
  if (type === 'currency') {
    return formatNumberToString(value)
  }

  return value
}

export default function DataTable({
  title,
  columns,
  rows,
  rowsPerPage,
  page,
  isLoading,
  error,
  onPageChange,
  onRowsPerPageChange,
  onRowClick,
  onNewClick,
}: Props) {
  const classes = useStyles()
  const newRows = isLoading ? getEmptyRows(rowsPerPage) : rows

  if (error) return <p>'An error has occurred: ' + error.message</p>

  return (
    <Paper className={classes.paper}>
      <Toolbar className={classes.toolbar}>
        <Typography
          className={classes.title}
          variant="h6"
          id="tableTitle"
          component="div"
        >
          {title}
        </Typography>
        <Tooltip title="Novo registro">
          <IconButton aria-label="Novo registro" onClick={onNewClick}>
            <AddIcon />
          </IconButton>
        </Tooltip>
      </Toolbar>
      <TableContainer>
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
            {newRows.map((row) => (
              <TableRow
                key={`table-row-${row.id}`}
                className={classes.bodyRow}
                onClick={() => {
                  onRowClick(row.id)
                }}
                hover
              >
                {columns.map((column) => (
                  <TableCell
                    key={`table-row-${row.id}-cell-${column.key}`}
                    align={column.align || 'left'}
                  >
                    {isLoading ? (
                      <Skeleton variant="text" />
                    ) : (
                      formatValue(column.type || 'text', row[column.key])
                    )}
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
    </Paper>
  )
}
