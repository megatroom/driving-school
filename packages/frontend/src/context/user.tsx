import {
  createContext,
  useReducer,
  useContext,
  useCallback,
  Reducer,
  useEffect,
} from 'react'
import { User, Menu, getUserProfile } from 'services/api/users'

export enum AuthStatus {
  idle,
  authenticated,
  unauthenticated,
}

interface State {
  authStatus: AuthStatus
  user?: User
  menu?: Menu[]
  setCurrentUser: (data: any) => void
}

const initialState = {
  authStatus: AuthStatus.idle,
  setCurrentUser: () => {},
}

const UserContext = createContext<State>(initialState)

const reducer: Reducer<State, any> = (state, action) => {
  switch (action.type) {
    case 'set-current-user':
      return {
        ...state,
        ...action.data,
        authStatus: AuthStatus.authenticated,
      }
    case 'logout':
      return {
        ...initialState,
        authStatus: AuthStatus.unauthenticated,
      }
    default:
      throw new Error()
  }
}

export const UserProvider: React.FC = ({ children }) => {
  const [state, dispatch] = useReducer(reducer, initialState)

  useEffect(() => {
    getUserProfile()
      .then(({ data }) => {
        dispatch({ type: 'set-current-user', data })
      })
      .catch(() => {
        dispatch({ type: 'logout' })
      })
  }, [])

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
