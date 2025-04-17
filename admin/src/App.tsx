import { radiantLightTheme, radiantDarkTheme } from 'react-admin';
import { HydraAdmin } from '@api-platform/admin';

function App() {
  return (
    <HydraAdmin
        entrypoint="https://localhost:8001/api"
        theme={radiantLightTheme}
        darkTheme={radiantDarkTheme}
    />
  )
}

export default App
