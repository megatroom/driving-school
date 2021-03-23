import { Outlet } from "react-router-dom";
import AppBar from "../../molecules/AppBar";

export default function PrivateLayout() {
  return (
    <>
      <AppBar />
      <Outlet />
    </>
  );
}
