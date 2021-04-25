import { useState } from 'react'
import { makeStyles } from '@material-ui/core/styles'
import clsx from 'clsx'
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
import AppBar from '@material-ui/core/AppBar'
import Skeleton from '@material-ui/lab/Skeleton'
import Alert from '@material-ui/lab/Alert'
import AlertTitle from '@material-ui/lab/AlertTitle'
import AddIcon from '@material-ui/icons/Add'
import DeleteIcon from '@material-ui/icons/Delete'
import ArrowDownwardIcon from '@material-ui/icons/ArrowDownward'

import { formatNumberToString } from 'formatters/number'
import {
  formatPayloadDate,
  formatPayloadDateTime,
  formatPayloadTime,
} from 'formatters/date'
import ConfirmDialog from 'atoms/ConfirmDialog'
import SearchBar from 'atoms/SearchBar'

const useStyles = makeStyles((theme) => ({
  paper: {
    width: '100%',
    marginBottom: theme.spacing(2),
    overflow: 'hidden',
  },
  table: {
    minWidth: 650,
  },
  header: {
    borderBottom: '1px solid rgba(0, 0, 0, 0.12)',
  },
  toolbar: {
    paddingLeft: theme.spacing(2),
    paddingRight: theme.spacing(1),
  },
  title: {
    flex: '1 1 100%',
  },
  orderIcon: {
    marginLeft: '3px',
    verticalAlign: 'middle',
  },
  tableHeadClickable: {
    cursor: 'pointer',
  },
  rowClickable: {
    cursor: 'pointer',
    textDecoration: 'underline',
  },
}))

type ColumnType = 'text' | 'currency' | 'datetime' | 'date' | 'time'

export interface Column {
  key: string
  label: string
  align?: 'left' | 'center' | 'right' | 'justify'
  type?: ColumnType
  onClick?: (id: number) => void
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
  switch (type) {
    case 'currency':
      return formatNumberToString(value)
    case 'datetime':
      return formatPayloadDateTime(value)
    case 'date':
      return formatPayloadDate(value)
    case 'time':
      return formatPayloadTime(value)
    default:
      return value
  }
}

interface Props {
  title: string
  primaryTextKey: string
  columns: Column[]
  total: number
  rows: any[]
  rowsPerPage: number
  order?: string
  page: number
  isLoading?: boolean
  error?: Error | null
  onPageChange: (event: unknown, newPage: number) => void
  onRowsPerPageChange: (event: any) => void
  onNewClick: () => void
  onDeleteClick?: (id: number) => void
  onOrderChange?: (key: string) => void
  onSearch?: (text: string) => void
}

type DeletePayload = { id: number; text: string } | undefined

export default function DataTable({
  title,
  primaryTextKey,
  columns,
  total,
  rows,
  rowsPerPage,
  order,
  page,
  isLoading,
  error,
  onPageChange,
  onRowsPerPageChange,
  onNewClick,
  onDeleteClick,
  onOrderChange,
  onSearch,
}: Props) {
  const classes = useStyles()
  const [deletePayload, setDeletePayload] = useState<
    DeletePayload | undefined
  >()
  const newRows = isLoading ? getEmptyRows(rowsPerPage) : rows
  const hasActions = !!onDeleteClick

  if (error) {
    return (
      <Alert severity="error">
        <AlertTitle>{`${title} - Erro`}</AlertTitle>
        {error.message}
      </Alert>
    )
  }

  return (
    <Paper className={classes.paper}>
      <AppBar
        className={classes.header}
        position="static"
        color="default"
        elevation={0}
      >
        <Toolbar className={classes.toolbar}>
          <Typography
            className={classes.title}
            variant="h6"
            id="tableTitle"
            component="div"
          >
            {title}
          </Typography>
          <SearchBar onChange={onSearch} />
          <Tooltip title="Novo registro">
            <IconButton aria-label="Novo registro" onClick={onNewClick}>
              <AddIcon />
            </IconButton>
          </Tooltip>
        </Toolbar>
      </AppBar>
      <TableContainer>
        <Table className={classes.table} aria-label="table">
          <TableHead>
            <TableRow>
              {columns.map((column) => (
                <TableCell
                  key={`table-column-${column.key}`}
                  align={column.align || 'left'}
                  className={clsx({ [classes.tableHeadClickable]: !!order })}
                  onClick={() => {
                    order && onOrderChange && onOrderChange(column.key)
                  }}
                >
                  {column.label}
                  {order && order === column.key && (
                    <span className={classes.orderIcon}>
                      <ArrowDownwardIcon fontSize="inherit" />
                    </span>
                  )}
                </TableCell>
              ))}
              {hasActions && <TableCell>&nbsp;</TableCell>}
            </TableRow>
          </TableHead>
          <TableBody>
            {newRows.map((row) => (
              <TableRow key={`table-row-${row.id}`} hover>
                {columns.map((column) => (
                  <TableCell
                    key={`table-row-${row.id}-cell-${column.key}`}
                    align={column.align || 'left'}
                    className={clsx({
                      [classes.rowClickable]: !!column.onClick,
                    })}
                    onClick={() => {
                      column.onClick && column.onClick(row.id)
                    }}
                  >
                    {isLoading ? (
                      <Skeleton variant="text" />
                    ) : (
                      formatValue(column.type || 'text', row[column.key])
                    )}
                  </TableCell>
                ))}
                {hasActions && (
                  <TableCell align="right">
                    <Tooltip title="Excluir">
                      <IconButton
                        aria-label="excluir"
                        size="small"
                        onClick={() =>
                          setDeletePayload({
                            id: row.id,
                            text: row[primaryTextKey],
                          })
                        }
                      >
                        <DeleteIcon fontSize="small" />
                      </IconButton>
                    </Tooltip>
                  </TableCell>
                )}
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
      <TablePagination
        rowsPerPageOptions={[5, 10, 25]}
        rowsPerPage={rowsPerPage}
        component="div"
        count={total}
        page={page}
        onChangePage={onPageChange}
        onChangeRowsPerPage={onRowsPerPageChange}
      />
      <ConfirmDialog
        id="delete"
        onCancel={() => {
          setDeletePayload(undefined)
        }}
        onConfirm={() => {
          onDeleteClick && onDeleteClick(deletePayload?.id || 0)
          setDeletePayload(undefined)
        }}
        open={!!deletePayload}
      >
        {`Confirma e exclusão do registro "${deletePayload?.text || ''}"?`}
      </ConfirmDialog>
    </Paper>
  )
}
