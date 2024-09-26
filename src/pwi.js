import { decodeEntities } from '@wordpress/html-entities'

const Content = () => {
    return decodeEntities(settings.description || '')
}

const Label = (props) => {
    const { PaymentMethodLabel } = props.components
    return <PaymentMethodLabel text={label} />
}

const { getSetting } = window.wc.wcSettings
const settings = getSetting('pwi_data', {})
const label = decodeEntities(settings.title)

const PwiOptions = {
    name: "pwi",
    label: <Label />,
    content: <Content />,
    edit: <Content />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings.supports,
    }
}

export default PwiOptions