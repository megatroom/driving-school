import {
  createContext,
  useReducer,
  useContext,
  useCallback,
  Reducer,
} from 'react'
import { User, Menu } from 'services/api/users'

interface State {
  isAuthenticated: boolean
  user?: User
  menu?: Menu
  setCurrentUser: (data: any) => void
}

const initialState = {
  isAuthenticated: false,
  setCurrentUser: () => {},
}

const UserContext = createContext<State>(initialState)

const reducer: Reducer<State, any> = (state, action) => {
  switch (action.type) {
    case 'set-current-user':
      return {
        ...state,
        ...action.data,
        isAuthenticated: true,
      }
    default:
      throw new Error()
  }
}

export const UserProvider: React.FC = ({ children }) => {
  const [state, dispatch] = useReducer(reducer, initialState)

  return (
    <UserContext.Provider
      value={{
        ...state,
        setCurrentUser: useCallback((data) => {
          dispatch({ type: 'set-current-user', data })
        }, []),
      }}
    >
      {children}
    </UserContext.Provider>
  )
}

export const useUser = () => useContext<State>(UserContext)
