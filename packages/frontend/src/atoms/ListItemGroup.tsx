import { useState } from 'react'
import { makeStyles } from '@material-ui/core/styles'
import List from '@material-ui/core/List'
import ListItem from '@material-ui/core/ListItem'
import ListItemIcon from '@material-ui/core/ListItemIcon'
import ListItemText from '@material-ui/core/ListItemText'
import Collapse from '@material-ui/core/Collapse'
import ExpandLess from '@material-ui/icons/ExpandLess'
import ExpandMore from '@material-ui/icons/ExpandMore'

const useStyles = makeStyles((theme) => ({
  nested: {
    paddingLeft: theme.spacing(4),
  },
}))

interface Items {
  id: number
  name: string
  onClick: () => void
}

interface Props {
  icon: object
  primary: string
  items: Items[]
}

export default function ListItemGroup({ icon, primary, items }: Props) {
  const classes = useStyles()
  const [open, setOpen] = useState(false)

  const handleClick = () => {
    setOpen(!open)
  }

  return (
    <>
      <ListItem onClick={handleClick} button>
        <ListItemIcon>{icon}</ListItemIcon>
        <ListItemText primary={primary} />
        {open ? <ExpandLess /> : <ExpandMore />}
      </ListItem>
      <Collapse in={open} timeout="auto" unmountOnExit>
        <List component="div" disablePadding>
          {items.map((item) => (
            <ListItem
              key={`group-inner-list-item-${item.id}`}
              className={classes.nested}
              onClick={item.onClick}
              button
            >
              <ListItemText primary={item.name} />
            </ListItem>
          ))}
        </List>
      </Collapse>
    </>
  )
}
