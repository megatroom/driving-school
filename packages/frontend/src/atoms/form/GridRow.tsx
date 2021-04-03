import Grid from '@material-ui/core/Grid'

const GridRow: React.FC = ({ children }) => (
  <Grid container spacing={3}>
    {children}
  </Grid>
)

export default GridRow
