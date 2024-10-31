import Svg from './Svg';
import {__} from '@wordpress/i18n';


const StatCard = ({title, value, icon, color}) => {
    return (
        <div className="bg-white rounded-lg shadow p-6 flex items-center space-x-4">
            <div className={`p-3 rounded-full ${color}`}>
                <Svg icon={icon}/>
            </div>
            <div>
                <p className="text-sm font-medium text-gray-500">{title}</p>
                <p className="text-2xl font-semibold text-gray-900">{value}</p>
            </div>
        </div>
    );
};

export default StatCard;