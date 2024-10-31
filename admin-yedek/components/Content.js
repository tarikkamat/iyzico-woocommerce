import Settings from "../pages/Settings";
import Dashboard from "../pages/Dashboard";

const Content = ({activeMenuItem}) => {
    const renderContent = () => {
        switch (activeMenuItem) {
            case 'dashboard':
                return <Dashboard/>
            case 'settings':
                return <Settings/>
            default:
                return <h1>404</h1>
        }
    }

    return (
        <>
            <div className="bg-white p-6 rounded shadow">
                {renderContent()}
            </div>
            <span className="mt-1">v3.5.8</span>
        </>
    )
}

export default Content