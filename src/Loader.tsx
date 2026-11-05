import App from '@hubleto/react-ui/core/App'
import Translator from '@hubleto/react-ui/core/Translator';
import FormCustomizer from '@hubleto/react-ui/core/FormCustomizer';
import TableClientTypes from './Components/FC/TableClientTypes'
import TabCustomerOhsData from './Components/FC/TabCustomerOhsData'

const parentApp = 'Hubleto/App/External/WaiBlue/OhsClients';
const T = new Translator(parentApp + '/Loader', 'Loader');

// Tab added to the customer form (community Customers app).
function mountClientDataTab() {
  return {title: T.translate('Occupational health'), content: () => <TabCustomerOhsData />};
}

class OhsClientsApp extends App {
  init() {
    super.init();
    globalThis.hubleto.registerReactComponent('OhsClientsTableClientTypes', TableClientTypes);
    FormCustomizer.addTab('FormCustomer', 'ohs-client-data', mountClientDataTab);
  }
}

globalThis.hubleto.registerApp(parentApp, new OhsClientsApp());
