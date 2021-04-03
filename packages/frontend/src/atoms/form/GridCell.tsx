import Grid, { GridSize } from '@material-ui/core/Grid'

interface Props {
  column: GridSize
}

const GridCell: React.FC<Props> = ({ children, column }) => (
  <Grid item xs={12} md={column}>
    {children}
  </Grid>
)

export default GridCell
