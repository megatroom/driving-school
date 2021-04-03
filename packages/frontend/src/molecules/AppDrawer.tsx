import { FC } from 'react'
import { useNavigate } from 'react-router-dom'
import { makeStyles } from '@material-ui/core/styles'
import Drawer from '@material-ui/core/Drawer'
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft'
import IconButton from '@material-ui/core/IconButton'
import List from '@material-ui/core/List'
import Divider from '@material-ui/core/Divider'
import EmojiTransportationIcon from '@material-ui/icons/EmojiTransportation'
import DescriptionOutlinedIcon from '@material-ui/icons/DescriptionOutlined'
import AccountBalanceOutlinedIcon from '@material-ui/icons/AccountBalanceOutlined'
import PrintOutlinedIcon from '@material-ui/icons/PrintOutlined'
import EqualizerIcon from '@material-ui/icons/Equalizer'
import SettingsOutlinedIcon from '@material-ui/icons/SettingsOutlined'

import { getRoutePath } from 'routes'
import { AuthStatus, useUser } from 'context/user'
import ListItemGroup from 'atoms/ListItemGroup'

export const drawerWidth = 240

const useStyles = makeStyles((theme) => ({
  drawer: {
    width: drawerWidth,
    flexShrink: 0,
  },
  drawerPaper: {
    width: drawerWidth,
  },
  drawerHeader: {
    display: 'flex',
    alignItems: 'center',
    padding: theme.spacing(0, 1),
    // necessary for content to be below app bar
    ...theme.mixins.toolbar,
    justifyContent: 'flex-end',
  },
}))

interface Props {
  open: boolean
  onClose: () => void
}

export const DrawerHeader: FC = (props) => {
  const classes = useStyles()

  return <div className={classes.drawerHeader} {...props} />
}

const groupIcon: Record<number, object> = {
  1: <EmojiTransportationIcon />,
  2: <DescriptionOutlinedIcon />,
  3: <AccountBalanceOutlinedIcon />,
  4: <PrintOutlinedIcon />,
  5: <EqualizerIcon />,
  6: <SettingsOutlinedIcon />,
}

export default function AppDrawer({ open, onClose }: Props) {
  const navigate = useNavigate()
  const classes = useStyles()
  const { authStatus, menu } = useUser()

  if (authStatus !== AuthStatus.authenticated) {
    return null
  }

  return (
    <Drawer
      className={classes.drawer}
      variant="persistent"
      anchor="left"
      open={open}
      classes={{
        paper: classes.drawerPaper,
      }}
    >
      <DrawerHeader>
        <IconButton onClick={onClose}>
          <ChevronLeftIcon />
        </IconButton>
      </DrawerHeader>
      <Divider />
      <List>
        {menu?.map((group) => (
          <ListItemGroup
            key={`menu-group-${group.id}`}
            icon={groupIcon[group.code]}
            primary={group.name}
            items={group.pages.map((page) => {
              return {
                ...page,
                onClick: () => {
                  navigate(getRoutePath(group.id, page.id))
                  onClose()
                },
              }
            })}
          />
        ))}
      </List>
    </Drawer>
  )
}
