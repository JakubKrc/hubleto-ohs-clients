import Input from '@hubleto/react-ui/components/fc/FormComponents/Input';

/**
 * Tab added to the community Customer form. The inputs are Customer custom columns,
 * so they are saved together with the customer.
 */
const TabCustomerOhsData = () => <div className='grid grid-cols-1 md:grid-cols-2 gap-2'>
  <Input field='id_ohs_client_type' />
  <Input field='ohs_employee_count' />
  <Input field='ohs_employee_count_at_risk' />
  <Input field='ohs_audit_interval_months' />
  <Input field='ohs_contract_valid_from' />
  <Input field='ohs_contract_valid_to' />
</div>;

export default TabCustomerOhsData;
