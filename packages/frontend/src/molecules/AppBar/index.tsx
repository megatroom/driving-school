import MuAppBar from "@material-ui/core/AppBar";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";
import Button from "@material-ui/core/Button";

export default function AppBar() {
  return (
    <MuAppBar position="static">
      <Toolbar>
        <Typography variant="h6">Driving School</Typography>
        <Button color="inherit">Login</Button>
      </Toolbar>
    </MuAppBar>
  );
}
