import { useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import { makeStyles } from '@material-ui/core/styles'
import Avatar from '@material-ui/core/Avatar'
import Button from '@material-ui/core/Button'
import Link from '@material-ui/core/Link'
import Grid from '@material-ui/core/Grid'
import LockOutlinedIcon from '@material-ui/icons/LockOutlined'
import Typography from '@material-ui/core/Typography'
import TextField from 'atoms/form/TextField'
import { LoginPayload, login } from 'services/api/auth'
import { AuthStatus, useUser } from 'context/user'
import { useEffect } from 'react'

const useStyles = makeStyles((theme) => ({
    avatar: {
        margin: theme.spacing(1),
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing(1),
    },
    submit: {
        margin: theme.spacing(3, 0, 2),
    },
}))

export default function Login() {
    const { control, handleSubmit } = useForm()
    const { authStatus, setCurrentUser } = useUser()
    const classes = useStyles()
    const navigate = useNavigate()

    const onSubmit = (payload: LoginPayload) => {
        login(payload)
            .then((res) => {
                setCurrentUser(res.data)
            })
            .catch(console.error)
    }

    useEffect(() => {
        if (authStatus === AuthStatus.authenticated) {
            navigate('/')
        }
    }, [authStatus, navigate])

    return (
        <>
            <Avatar className={classes.avatar}>
                <LockOutlinedIcon />
            </Avatar>
            <Typography component="h1" variant="h5">
                Sign in
            </Typography>
            <form className={classes.form} onSubmit={handleSubmit(onSubmit)}>
                <TextField
                    control={control}
                    autoComplete="login"
                    id="login"
                    label="Usuário"
                    autoFocus
                    required
                />
                <TextField
                    control={control}
                    id="password"
                    type="password"
                    label="Senha"
                    autoComplete="current-password"
                    required
                />
                <Button
                    type="submit"
                    variant="contained"
                    color="primary"
                    className={classes.submit}
                    fullWidth
                >
                    Entrar
                </Button>
                <Grid container>
                    <Grid item xs>
                        <Link href="#" variant="body2">
                            Esqueceu a senha?
                        </Link>
                    </Grid>
                </Grid>
            </form>
        </>
    )
}
