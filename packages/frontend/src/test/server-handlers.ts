import { rest } from 'msw' // msw supports graphql too!

const baseURL = 'http://localhost:5000/api'

const buildListResponse = (list: any) => ({
  total: list.length,
  data: list,
})

const handlers = [
  rest.get(`${baseURL}/users/profile`, async (req, res, ctx) => {
    return res(
      ctx.json({ user: { id: 1, login: 'admin', name: 'Admin' }, menu: [] })
    )
  }),
  rest.get(`${baseURL}/cars/types`, async (req, res, ctx) => {
    return res(
      ctx.json(
        buildListResponse([
          { id: 1, description: 'Car', commission: 10 },
          { id: 2, description: 'Motorcycle', commission: 8 },
          { id: 3, description: 'Truck', commission: 5 },
        ])
      )
    )
  }),
]

export { handlers }
