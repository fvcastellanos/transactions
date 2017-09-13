<?php
/**
 * Created by PhpStorm.
 * User: fvcg2
 * Date: 9/11/2017
 * Time: 10:22 PM
 */

namespace AppBundle\Service;


use AppBundle\Model\BeneficiaryDao;
use Psr\Log\LoggerInterface;

class BeneficiaryService extends BaseService
{
    private $logger;
    private $beneficiaryDao;

    public function __construct(LoggerInterface $logger,
                                BeneficiaryDao $beneficiaryDao) {
        $this->logger = $logger;
        $this->beneficiaryDao = $beneficiaryDao;
    }

    public function getBeneficiariesFor($profileId) {
        try {
            $beneficiaries = $this->beneficiaryDao->getBeneficiariesFor($profileId);

            return $this->returnValue($beneficiaries);
        } catch (\Exception $ex) {
            $this->logger->error("can't get beneficiaries: ", [$ex]);
            return $this->returnError($ex->getMessage());
        }
    }
}