import serverApp from "./app";
import config from "./config";

const PORT = config.port;

serverApp.listen(PORT, () => {
  console.log(`Server started on port ${PORT}`);
});
